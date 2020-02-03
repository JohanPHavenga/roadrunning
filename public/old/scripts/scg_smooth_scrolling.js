/* 
 * Smooth scrolling on page load for hash links
 */

/**
 * Smooth scrolling for hash window url
 * @param {int} scrollSpeed page scroll speed in milliseconds. Default is 1000
 * @param {string} highlightColor CSS highlight color value in Hex. or pass FALSE to disable this feature. Default is #F8F99A
 * @author nick
 * @link http://www.secretsofgeeks.com
 * @returns {NULL}
 */
function scg_smooth_hash_scroll(scrollSpeed, highlightColor) {
   /* Return if jQuery not inlcuded */
   if (typeof jQuery !== "function") {
       console.warn('jQuery not found!');
       return false;
   }

   /* Default Values */
   scrollSpeed = (typeof scrollSpeed === "undefined") ? 1000 : scrollSpeed;
   highlightColor = (typeof highlightColor === "undefined") ? "#F8F99A" : highlightColor;

   var hash_value = window.location.hash;

   if (hash_value) {
       /* Prevent Default scrolling */
       setTimeout(function (){
           window.scrollTo(0, 0); 
       }, 1);

       /* Append head to add highlight css */
       if (highlightColor) {                           
           jQuery(document).ready(function (){
               var scg_css = '*.scg-highlight-target:target { animation: scg_highlight 1.5s; -moz-animation: scg_highlight 1.5s; -webkit-animation: scg_highlight 1.5s; } '+
                             '@keyframes scg_highlight { 50% {background: '+highlightColor+';} } '+
                             '@-moz-keyframes scg_highlight { 50% {background: '+highlightColor+';} } '+
                             '@-webkit-keyframes scg_highlight { 50% {background: '+highlightColor+';} }';
               jQuery("<style type='text/css'> " + scg_css + " </style>").appendTo("head");
           });
       }
   }

   jQuery(window).load(function (){
       /* Scroll to position and add class for css animation */
       if (jQuery(hash_value).length > 0) {
           scg_element_obj = jQuery(hash_value);
           window.scroll(0, 0); /* Prevent Default scrolling for chrome and opera */
           jQuery('html, body').animate( {scrollTop: scg_element_obj.offset().top}, scrollSpeed, function (){
               scg_element_obj.addClass('scg-highlight-target');
           });
       }
   });
}

/* uses 
 * Note: keep that function outside the load and ready function of jquery or js
 */
scg_smooth_hash_scroll();