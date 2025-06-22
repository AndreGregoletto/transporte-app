<x-layout.base>

    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Importar Frequências</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Upload do Arquivo de Frequências</h6>
            </div>
            <div class="card-body">
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Selecione o arquivo frequencies.txt</label>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".txt">
                    </div>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </form>
                <div id="response" class="mt-3"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#importForm').on('submit', function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        url: '{{ route("import.frequencies") }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#response').html('<div class="alert alert-success">' + response.message + '</div>');
                        },
                        error: function (xhr) {
                            $('#response').html('<div class="alert alert-danger">' + xhr.responseJSON.error + '</div>');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layout.base>