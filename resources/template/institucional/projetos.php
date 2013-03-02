<div class="span11">
    <h1>Meus Projetos</h1>
    <div class="row-fluid span11">
        <div class="span11 boxRepositories">
        </div><!--/span-->
    </div><!--/span-->
</div><!--/span-->
<script src="/script/githubRepos.js"></script>
<script src="/script/githubAPIRender.js"></script>
<script type="text/javascript">
    $(function(){
        $.get('/api/v1/integration/github',function(data){
            var github = new githubRepos(data);
            var render = new githubAPIRender();
            var repos = github.getRepositories();
            var $boxRepositories = $('.boxRepositories');
            for(var k in repos){
                $boxRepositories.append(render.reposRender(repos[k]));
            }
        },'json');
    });
</script>
