<button
    type="{{ $to ? 'button' : 'submit' }}"
    class="btn btn-{{ $color }} btn-{{ $size }} {{ $block ? 'btn-block' : '' }}"
    id="button-create"
    onclick="to('{{ $to }}')">

    {{ $icon ?? '' }}

    <div class="spinner-grow spinner-grow-sm mb-1 d-none" role="status" id="spinner"></div>

    <span id="title" class="my-1">{{ $title }}</span></button>

<script charset="utf-8" defer>
    function to(to) {
        if(to) {
            window.location = to
        }
        //let buttonCreate = document.getElementById('button-create').disabled = true;
        let icon = document.getElementById('icon').classList.add("d-none");
        let title = document.getElementById('title').innerHTML = 'Loading...';
        let spinner = document.getElementById('spinner').classList.remove("d-none");

        return true;
    }
</script>
