function errorDisplayWithParent(parentElement, response)
{
    $.each(response, function (key, value) {
        let element = $('#'+parentElement+' #'+key);

        $('#'+parentElement+' div.'+key).find('.error-'+key).remove();
        element.closest('#'+parentElement+' div.'+key).append('<p class="text-danger error-'+key+'">'+value+'</p>');
    });
}
