<td class="text-center">
    <a href="{{ $editUrl }}" class="btn btn-sm btn-primary">
        <i class="fa fa-pencil-alt"></i> {{ $editText ?? 'EDIT' }}
    </a>
    
    <button onClick="Delete(this.id)" class="btn btn-sm btn-danger" id="{{ $deleteId }}">
        <i class="fa fa-trash"></i>
    </button>
</td>