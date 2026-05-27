import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["messages", "input"];

    async sendMessage(event) {
        event.preventDefault();
        const content = this.inputTarget.value;
        console.log(this.inputTarget.value);
        if (!content) return;
        // Create an empty AI message element
        const aiMessage = document.createElement('div');
        this.messagesTarget.appendChild(aiMessage);

        const response = await fetch('/api/ask', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question: content })
        });

        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';
        let answer = '';

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            buffer += decoder.decode(value, { stream: true });

            // The server sends newline-delimited JSON. Parse complete lines only.
            let newlineIndex;
            while ((newlineIndex = buffer.indexOf('\n')) !== -1) {
                const line = buffer.slice(0, newlineIndex).trim();
                buffer = buffer.slice(newlineIndex + 1);
                if (!line) {
                    continue;
                }

                let textChunk = line;
                try {
                    const payload = JSON.parse(line);
                    textChunk = payload.text ?? line;
                } catch {
                    // Fallback: the response may be plain text instead of NDJSON.
                }
                answer += textChunk;
                aiMessage.innerHTML += textChunk + '<br/>';
            }
        }

        if (buffer.trim()) {
            try {
                const payload = JSON.parse(buffer.trim());
                answer += payload.text ?? buffer;
                aiMessage.innerHTML += payload.text ?? buffer;
            } catch (error) {
                aiMessage.innerHTML += buffer + '<br/>';
                console.warn('Failed to parse trailing stream chunk:', buffer, error);
            }
        }

        if (answer) {
            const createHistory = await fetch('/api/create/history', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ question: content, answer: answer })
            });
        }
    }
}
