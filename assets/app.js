import './stimulus_bootstrap.js';
import { registerVueControllerComponents } from '@symfony/ux-vue';
import HistoryList from './vue/controllers/HistoryList.js';
registerVueControllerComponents({
    'HistoryList': HistoryList
});
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
