/**
 * this file is used to make global event
 * becaus some components like Accept.vue is child component tha has two parent they are Vote and Answer and grandparent is Answers
 * we need send data directly from Accept component to grandparent component Answers
 * to prevent need to load the page to make one check becaus currently when check accept we may have two accepts an wee need t load page again to remove one
 */

import Vue from 'Vue';

const eventBus = new Vue();

export default eventBus;
