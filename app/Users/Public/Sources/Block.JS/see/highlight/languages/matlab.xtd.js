/*!
 * Block.JS Framework Source Code
 *
 * static see.highlight.language
 *
 * Date: 2017-04-06
 */
;
block('$_/see/highlight/highlight.xtd', function(_, global, undefined) {
    _.see.highlight.languages.matlab = {
        // We put string before comment, because of printf() patterns that contain "%"
        'string': /\B'(?:''|[^'\n])*'/,
        'comment': [
            /%\{[\s\S]*?\}%/,
            /%.+/
        ],
        // FIXME We could handle imaginary numbers as a whole
        'number': /\b-?(?:\d*\.?\d+(?:[eE][+-]?\d+)?(?:[ij])?|[ij])\b/,
        'keyword': /\b(?:break|case|catch|continue|else|elseif|end|for|function|if|inf|NaN|otherwise|parfor|pause|pi|return|switch|try|while)\b/,
        'function': /(?!\d)\w+(?=\s*\()/,
        'operator': /\.?[*^\/\\']|[+\-:@]|[<>=~]=?|&&?|\|\|?/,
        'punctuation': /\.{3}|[.,;\[\](){}!]/
    };
});