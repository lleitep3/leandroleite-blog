
<div class="hero-unit row">
    <div class="span2">
        <img src="https://lh3.googleusercontent.com/-hb7RdDL5Wtg/AAAAAAAAAAI/AAAAAAAAADE/U5Maa4hq6tA/s250-c-k/photo.jpg" class="img-polaroid">
    </div>
    <div class="span9">
        <h1>Leandro Leite</h1>
        <p>Desenvolvedor Web, Apaixonado pela familia, por Desenvolvimento, OpenSource, Filosofia e Conhecimentos Gerais... Ah, e por <b>PHP</b> é claro!</p>
        <p><a class="btn btn-primary btn-large" href="/leandroleite">Saiba mais do mesmo? &raquo;</a></p>
    </div>
</div>
<div class="span11">
    <h1>Últimos Artigos</h1>
    <div class="row-fluid">
        <div id="articlesContainer" class="span11">
            <img src="/image/loading1.gif">
        </div>
    </div><!--/span-->
</div><!--/span-->
<script src="/script/googleArticles.js"></script>
<script type="text/javascript">
    $(function() {
        $.get('/api/v1/integration/google', function(data) {
            var googleArticlesObject = new googleArticles(data);
            $('#articlesContainer').html(googleArticlesObject.getRender());
        }, 'json');
    });
</script>