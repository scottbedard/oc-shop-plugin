import Vue from 'vue';

/**
 * Lookup a language string.
 *
 * @param  {String} value
 * @param  {Object} lang
 * @return {String}
 */
const getLanguageString = function (value, lang) {
    for (let key of value.split('.')) lang = lang[key];
    return lang || value;
};

/**
 * Translate a string.
 * @param  {String} value
 * @param  {Object} lang
 * @param  {Object} data
 * @return {String}
 */
export default function(value, lang, data = {}) {
    // grab the string we're going to translate
    let translatedValue = getLanguageString(value, lang);

    // translate the passed in data
    for (let key in data) {
        data[key] = getLanguageString(data[key], lang);
    }

    // find language strings in our value and replace them
    for (let word of translatedValue.split(' ')) {
        if (word.startsWith(':')) {
            let wordKey = word.slice(1);
            if (typeof data[wordKey] !== 'undefined') {
                translatedValue = translatedValue.replace(word, data[wordKey]);
            }
        }
    }

    // finally, return the translated string
    return translatedValue;
};
