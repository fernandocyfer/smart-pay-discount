# Smart Pay Discount

Um plugin WordPress que aplica descontos automáticos baseados no método de pagamento selecionado no WooCommerce.

## Descrição

O Smart Pay Discount é um plugin que permite aplicar descontos percentuais automaticamente quando um método de pagamento específico é selecionado no checkout do WooCommerce. O plugin oferece várias opções de configuração para personalizar o comportamento do desconto.

### Funcionalidades

- Aplica automaticamente um valor de desconto percentual quando um método de pagamento específico é selecionado
- Bloqueia a aplicação do desconto se o carrinho contiver produtos de categorias excluídas
- Bloqueia a aplicação do desconto se houver qualquer cupom ativo no carrinho
- Atualiza os totais em tempo real via AJAX ao trocar o método de pagamento
- Interface administrativa amigável e responsiva

### Requisitos

- WordPress 5.8 ou superior
- PHP 7.4 ou superior
- WooCommerce 5.0 ou superior

## Instalação

1. Faça o upload da pasta `smart-pay-discount` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Vá para WooCommerce > Smart Pay Discount para configurar o plugin

## Configuração

1. **Ativar Plugin**: Marque esta opção para ativar o plugin
2. **Percentual de Desconto**: Defina o valor percentual do desconto (ex: 5 para 5%)
3. **Métodos de Pagamento**: Selecione quais métodos de pagamento concederão desconto
4. **Categorias Excluídas**: Selecione as categorias de produtos que não receberão desconto
5. **Ignorar com Cupom**: Marque esta opção para não aplicar o desconto quando houver cupom no carrinho

## Perguntas Frequentes

### O desconto é aplicado em todos os produtos?

Sim, o desconto é aplicado em todos os produtos do carrinho, exceto aqueles que pertencem às categorias excluídas nas configurações.

### O desconto funciona com cupons?

Por padrão, o plugin não aplica o desconto quando há cupons no carrinho. Esta opção pode ser desativada nas configurações.

### O desconto é atualizado em tempo real?

Sim, o desconto é atualizado automaticamente via AJAX quando o método de pagamento é alterado no checkout.

## Changelog

### 1.0.0
- Lançamento inicial

## Créditos

Desenvolvido por [Fernando Filho](https://github.com/fernandofilho)

## Licença

Este plugin é licenciado sob a GPL v2 ou posterior. 