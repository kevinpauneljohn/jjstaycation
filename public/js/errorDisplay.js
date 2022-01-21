function errorDisplay(response)
{
    $.each(response, function (key, value) {
        let element = $('#'+key);

        $('div.'+key).find('.error-'+key).remove();
        element.closest('div.'+key).append('<p class="text-danger error-'+key+'">'+value+'</p>');
    });
}
