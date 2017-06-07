/*!
 * Interblocks Framework Source Code
 *
 * static see.highlight.language
 *
 * Date: 2017-04-06
 */
;
iBlock('$_/see/highlight/highlight.xtd', function(_, global, undefined) {
    _.see.highlight.languages.julia = {
        'comment': {
            pattern: /(^|[^\\])#.*/,
            lookbehind: true
        },
        'string': /"""[\s\S]+?"""|'''[\s\S]+?'''|("|')(\\?.)*?\1/,
        'keyword': /\b(abstract|baremodule|begin|bitstype|break|catch|ccall|const|continue|do|else|elseif|end|export|finally|for|function|global|if|immutable|import|importall|let|local|macro|module|print|println|quote|return|try|type|typealias|using|while)\b/,
        'boolean': /\b(true|false)\b/,
        'number': /\b-?(0[box])?(?:[\da-f]+\.?\d*|\.\d+)(?:[efp][+-]?\d+)?j?\b/i,
        'operator': /\+=?|-=?|\*=?|\/[\/=]?|\\=?|\^=?|%=?|÷=?|!=?=?|&=?|\|[=>]?|\$=?|<(?:<=?|[=:])?|>(?:=|>>?=?)?|==?=?|[~≠≤≥]/,
        'punctuation': /[{}[\];(),.:]/
    };
});