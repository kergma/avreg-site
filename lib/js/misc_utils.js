(function($) {
    /**
     * Based on https://gist.github.com/mekwall/1263939
     * @param forcedUpdate  do not append events, just recalculate
     * @returns {*}
     */
    $.fn.textfill = function(forcedUpdate) {
        var $this = $(this);

        var doResize = function () {
            var ourText = $this.children("span"),
                parent = ourText.parent(),
                maxWidth = parent.width(),
                fontSize = parseInt(ourText.css("fontSize"), 10),
                multiplier = maxWidth / ourText.width(),
                newSize = (fontSize * (multiplier - 0.1));

            ourText.css( "fontSize", newSize);
        };

        if (!forcedUpdate) {
            // todo check if handler is already attached and skip if so (way to get rid of forceUpdate option)
            $(window).on('resize', function(){
                $this.each(doResize)
            })
        }

        return $this.each(doResize);
    };
})(jQuery);