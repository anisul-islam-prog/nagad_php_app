<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


    </div><!--maincontainer ends-->
    <!--<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>-->

</body>
</html>

  
  <script type="text/javascript">
  document.observe('dom:loaded', function(evt) {
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text: "Oops, nothing found!"},
      '.chosen-select-width'     : {width: "95%"}
    }
    var results = [];
    for (var selector in config) {
      var elements = $$(selector);
      for (var i = 0; i < elements.length; i++) {
        results.push(new Chosen(elements[i],config[selector]));
      }
    }
    return results;
  });
  </script>