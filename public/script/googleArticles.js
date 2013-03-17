function googleArticles(data) {
    this.provider = data;
    function getItem(article) {
        var html = '';
        
        var date = new Date(article.createdDate);
        var created = date.getDate()
                    + '/' + date.getMonth()
                    + '/' + date.getFullYear()
                    + ' ' + date.getHours()
                    + ':' + date.getMinutes()
                    + ':' + date.getSeconds();

        var date = new Date(article.modifiedDate);
        var modified = date.getDate()
                    + '/' + date.getMonth()
                    + '/' + date.getFullYear()
                    + ' ' + date.getHours()
                    + ':' + date.getMinutes()
                    + ':' + date.getSeconds();
        
        for (var k in article.owners) {
            html += '<p>' +
                    '<small>' +
                    '<a href="/sobremim" class="author">' + article.owners[k].displayName + '</a>' +
                    '</small>' +
                    '</p>';
        }
        html += '<p><small>Criado em : ' + created + '</small></p>';
        html += '<p><small>Modificado em : ' + modified + '</small></p>';
        return '<div class="boxArtigosPequeno">' +
                '<hr>' +
                '<h3><a href="/artigos/'+article.id+'">' + article.title + '</a></h3>' +
//                '<p>' +
//                ' Descrição de: ' + article.title +
//                '</p>' +
//                '<p>' +
//                '<small><b>Tags: </b>' +
//                '<span class="badge badge-info">teste</span>' +
//                '<span class="badge badge-info">teste</span>' +
//                '<span class="badge badge-info">teste</span>' +
//                '</small>' +
//                '</p>' +
                html +
                '</div>';
    }
    ;
    googleArticles.prototype.getRender = function() {
        var html = '';
        for (var k in this.provider) {
            html += getItem(this.provider[k]);
        }
        return html;
    };
}