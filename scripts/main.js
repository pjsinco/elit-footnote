jQuery(document).ready(function($) {

  var $bigfoot = $.bigfoot({
    //actionOriginalFN: 'ignore',
    activateCallback: function($popover, $button) {
      console.log($popover);
      console.log($button);
    },
    //allowMultipleFN: false,
    anchorPattern: /(fn|footnote|note|popup)[:\-_\d]/gi,
    //appendPopoversTo: 'body',
    //preventPageScroll: false,

  });
  //var breakpoint = $bigfoot.addBreakpoint('(max-width: 480px)');
});
