<script src="/script/googleArticles.js"></script>
<script type="text/javascript">
    $(function() {
        $.get('/api/v1/integration/google', function(data) {
            var googleArticlesObject = new googleArticles(data);
            $('#articlesContainer').html(googleArticlesObject.getRender());
        }, 'json');
    });
</script>

<div class="span11">
    <h1>Últimos Artigos</h1>
    <div class="row-fluid">
        <div id="articlesContainer" class="span11">
            <img src="/image/loading1.gif">
        </div>
    </div><!--/span-->
</div><!--/span-->
