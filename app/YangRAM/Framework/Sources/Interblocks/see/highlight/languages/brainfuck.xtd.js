/*!
 * Interblocks Framework Source Code
 *
 * static see.highlight.language
 *
 * Date: 2017-04-06
 */
;
iBlock('$_/see/highlight/highlight.xtd', function(_, global, undefined) {
    _.see.highlight.languages.brainfuck = {
        'pointer': {
            pattern: /<|>/,
            alias: 'keyword'
        },
        'increment': {
            pattern: /\+/,
            alias: 'inserted'
        },
        'decrement': {
            pattern: /-/,
            alias: 'deleted'
        },
        'branching': {
            pattern: /\[|\]/,
            alias: 'important'
        },
        'operator': /[.,]/,
        'comment': /\S+/
    };
});