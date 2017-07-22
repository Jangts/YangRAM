/*!
 * Block.JS Framework Source Code
 *
 * static see.highlight.language
 *
 * Date: 2017-04-06
 */
;
block('$_/see/highlight/highlight.xtd', function(_, global, undefined) {
    _.see.highlight.languages.ini = {
        'comment': /^[ \t]*;.*$/m,
        'important': /\[.*?\]/,
        'constant': /^[ \t]*[^\s=]+?(?=[ \t]*=)/m,
        'attr-value': {
            pattern: /=.*/,
            inside: {
                'punctuation': /^[=]/
            }
        }
    };
});