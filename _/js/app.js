// Loaded on every page

function rejig_section_selectors () {
  var $section = $('.page-template-page-section-php');
  if (!$section.length) { 
    $section = $('.page-info'); 
  }

  if ($section.length > 0) {
    selector_height = $section.find('.section-selector').outerHeight();
    content_height = $section.find('.text-article').outerHeight();
    console.log('rejig');
    if (selector_height < content_height) {
      $section.find('.section-selector')
        .css('position', 'absolute');
    }
  }
}

$(function() {
  rejig_section_selectors();
});
