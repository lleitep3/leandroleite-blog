function githubArticles(data){
    this.provider = data;
    githubArticles.prototype.getArticles = function(){
        var data = [];
        for(var k in this.provider){
            var article = this.provider[k];
            data.push({
                name: article.name.str_replace('.md',''),
                description: ''
                });
        }
        
        return data;
    };
}