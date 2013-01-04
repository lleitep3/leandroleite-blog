function githubAPIRender(){
    githubAPIRender.prototype.reposRender = function(repo){
        
        var $html = $('<div class="boxProjeto"/>')
        .append('<hr/>')
        .append('<h4 class="subTitle">'+repo.name+'</h4>')
        .append('<p>'+repo.description+'</p>');
        
        $('<p/>').append('<b>Tags: </b>')
        .append('<span class="badge badge-info">'+repo.tag+'</span>')
        .appendTo($html);
        
        $('<abbr>Ultima Alteração: ' + repo.lastUpdated + '  </abbr>')
        .append(' <a href="'+repo.url+'">Github</a>')
        .appendTo($html);
        return $html;
    };
}