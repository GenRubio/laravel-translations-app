<br>
<button class="btn btn-secondary btn-sm" id="googleTranslateButton-{{ $lang }}">
    <i class="las la-language"></i> Translate
</button>


<script>
    (function() {
        let button = document.getElementById("googleTranslateButton-{{ $lang }}");
        button.addEventListener("click", function(ev) {
            ev.preventDefault();
            let textEs = document.getElementsByName("laguages[es]")[0].value
            console.log(textEs);
            let outputElement = document.getElementsByName("laguages[{{ $lang }}]");
            outputElement[0].value = textEs;
        });
    })();
</script>
