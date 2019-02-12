$(document).ready(function(){
    // tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // collapses (filter div)
    $('.collapse')
        .on('shown.bs.collapse', function(){
            $(this).parent().find('.fa.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
        })
        .on('hidden.bs.collapse', function(){
            $(this).parent().find('.fa.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
        });

    // modal
    $('#modal_confirm').on('show.bs.modal', openModal);
});

function openModal(e) {
    const title = $(e.relatedTarget).data('title');
    const body = $(e.relatedTarget).data('body');
    const button = $(e.relatedTarget).data('button');
    const bclass = $(e.relatedTarget).data('bclass');
    const href = $(e.relatedTarget).data('href');
    $(this).find('.modal-title').text(title);
    $(this).find('.modal-body p').text(body);
    $(this).find('.btn-ok').attr('href', href).text(button).addClass(bclass);
}
