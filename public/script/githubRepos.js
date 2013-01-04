function githubRepos(data){
    var provider = [];
    for(var k in data){
        var repo = data[k];
        var date = new Date(repo.updated_at);
        provider.push({
            name : repo.name,
            url : repo.html_url,
            description : repo.description,
            tag : repo.language,
            lastUpdated : date.getDate()
            + '/' + date.getMonth() 
            + '/' + date.getFullYear()
            + ' ' + date.getHours() 
            + ':' + date.getMinutes() 
            + ':' + date.getSeconds()
        });
    }
    githubRepos.prototype.provider = provider;
    githubRepos.prototype.getRepositories = function(){
        return githubRepos.prototype.provider;
    };
}