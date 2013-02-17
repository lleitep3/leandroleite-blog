<div class="span11">
    <h1>Meus Projetos</h1>
    <div class="row-fluid span11">
        <div class="span11 boxArticles">
        </div><!--/span-->
    </div><!--/span-->
</div><!--/span-->
<script src="/script/githubArticles.js"></script>
<script src="/script/githubArticlesAPIRender.js"></script>
<script type="text/javascript">
    $(function(){
        $.get('/githubrepos',function(data){
            var github = new githubArticles(data);
            var render = new githubAPIRender();
            var repos = github.getRepositories();
            var $boxRepositories = $('.boxRepositories');
            for(var k in repos){
                $boxRepositories.append(render.reposRender(repos[k]));
            }
        },'json');
    });
</script>

<div class="span11">
    <h1>Ultimos Artigos</h1>
    <div class="row-fluid">
        <div class="span11">
            <div class="boxArtigosPequeno">
                <hr>
                <h3>Titulo Artigo</h3>
                <p>
                    Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste 
                </p>
                <p>
                    <small><b>Tags: </b>
                        <span class="badge badge-info">teste</span>
                        <span class="badge badge-info">teste</span>
                        <span class="badge badge-info">teste</span>
                    </small>
                </p>
                <p>
                    <small>
                        <a href="/sobremim" class="author">Leandro Leite</a>
                        - 20/11/2012 18:00
                    </small>
                </p>
            </div><!--/span-->
        </div><!--/span-->
    </div><!--/span-->
</div><!--/span-->
