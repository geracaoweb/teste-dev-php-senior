# Code Review - Matheus Fidelis

Opa Juninho, beleza? Recebi seu Pull request. Realmente está muito legal, amigo!
Trouxe aqui umas sugestões legais pra melhor seu projeto. Dividi em partes.

## Ambiente - Docker

Notei que você conseguiu subir todo nosso ambiente com o Docker. Isso é legal!
Porém, o Docker é utilizado em muitos outros lugares além da máquina local, e uma das principais features do mesmo é poder fazer deploy da mesma stack de desenvolvimento para o Cluster de produção.
Sua Stack estava perfeita para desenvolvimento, mas para o nosso Deploy vamos precisar de um pouco mais de hard configs no PHP, trocar nosso Web Server para o Nginx e configurá-lo para diminuir cada vez mais latência e aguentar mais concorrência na sua API. Fora que nesse caso, teríamos uma divergência muito grande entre os ambientes de desenvolvimento e produção se utilizarmos Nginx lá no Cloud e Apache nas máquinas locais. Isso vai também contra uma das principais finalidades do Docker, não é mesmo?

Na Stack do PHP tomei a liberdade de utilizar uma arquitetura que gira em torno do PHP-FPM e utilizei o Nginx para gerenciar os requests até o mesmo de uma forma mais performática. Essa arquitetura nos ajuda em ambientes de alta demanda, pois nos permite separar e escalar separadamente as máquinas de PHP das de Web Servers. Quando isso for pra produção, temos que tomar um cuidado bem grande nesses pontos.

Para escalarmos tanto em produção quanto pra agilizar a produtividade do desenvolvedor, criei um container pra você que tem a única finalidade de satisfazer as dependências do Composer da nossa aplicação.

Pronto, nesse formato a arquitetura do ambiente da sua API vai ficar sensacional!
