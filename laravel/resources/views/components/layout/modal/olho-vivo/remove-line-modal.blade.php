<div 
    class="modal fade" 
    id="removeLineModal-{{ $id }}" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="removeLineModalLabel-{{ $id }}" 
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeLineModalLabel-{{ $id }}">Confirmar Remoção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja remover a linha <strong>{{ $lt }}-{{ $tl }} ({{ $sl == 1 ? $tp : $ts }})</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('olhoVivo.removeLine', ['id' => $id, 'cl' => $cl]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remover</button>
                </form>
            </div>
        </div>
    </div>
</div>