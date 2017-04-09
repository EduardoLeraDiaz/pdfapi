(function () {
    function submitForm() {
        var formData = new FormData();
        var data = JSON.stringify({
            'title': $('#inputTitle').val(),
            'description': $('#inputdescription').val()
        });
        formData.append('data', data);
        formData.append('document', $('#inputFile')[0].files[0]);

        $.ajax({
            url: '/documents',
            data: formData,
            processData: false,
            contentType: false,
            method: 'POST',
            success: function () {
                $('#upload-modal').modal('hide');
                $('.pdf-list').trigger('list-upload');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                alert(errorThrown);
            }
        })
    }

    $(document).ready(function () {
        $('#upload-modal').on('show.bs.modal', function (event) {
            var modal = $(this);
            modal.find('form')[0].reset();
        });

        $('#upload-modal .upload-action').click(submitForm)
    });
}());
