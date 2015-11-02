
$(document).ready(function () {
    $('label.tree-toggler').parent().children('ul.tree').toggle(300);
    $('label.tree-toggler').click(function () {
        $(this).parent().children('ul.tree').toggle(300);
    });
    $('a.see-file').click(function(e) {
        $this = this;
        e.preventDefault
        $('#file-content').load($($this).attr('href'), function() {
            $('#file-title').text($($this).siblings('span').text());
        });
        return false;
    });
});