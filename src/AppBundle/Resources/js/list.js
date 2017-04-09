(function () {
    var lineTemplate = '<tr><td>$title</td><td>$description</td><td><a target="_blank" href="/documents/$id"><i class="fa fa-eye" aria-hidden="true"></i></a><i data-id="$id" class="fa fa-times action-delete" aria-hidden="true" data-toggle="modal" data-target="#delete-modal"></i></td></tr>';

    function uploadList() {
        var list = $(this);
        $.ajax({
            url: '/documents',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                removeLinesFromList(list);
                console.log(data.documents);
                addLinestoList(list, data.documents);
            },
            error: function () {

            }
        });
    }

    function removeLinesFromList(list) {
        list.find('tbody tr').remove();
    }

    function addLinestoList(list, dataLines) {
        for (var index = 0; index < dataLines.length; index++) {
            var element = dataLines[index];
            var line = lineTemplate
                .replace('$title', element.title)
                .replace('$description', element.description)
                .replace(/\$id/gi, element.id);
            list.find('tbody').append($(line));
        }
    }

    function deleteDocument() {
        var id = $(this).data('id');
        $.ajax({
            url: '/documents/'+id,
            method: 'DELETE',
            dataType: 'json',
            success: function (data) {
                $('.pdf-list').trigger('list-upload');
                $('#delete-modal').modal('hide');
            },
            error: function () {

            }
        });
    }

    $(document).ready(function () {
        $('.pdf-list').on('list-upload', uploadList);
        $('.pdf-list').trigger('list-upload');
        $('#delete-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            $(this).find('.delete-action').attr('data-id', id);
        });
        $('#delete-modal .delete-action').click(deleteDocument);

    });
}());
