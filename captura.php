<?php
// Permite requisições de outras origens (CORS), útil para testes locais
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Obtém o corpo da requisição (JSON vindo do formulário Javascript)
$dados = json_decode(file_get_contents("php://input"), true);

// Se não vierem dados (por exemplo, se alguém acessar a URL direto), interrompe
if (!$dados) {
    echo json_encode(["status" => "erro", "mensagem" => "Nenhum dado recebido."]);
    exit;
}

// Extraindo as variáveis
$tipo = isset($dados['tipo']) ? $dados['tipo'] : 'Lead do Site';
$nome = isset($dados['nome']) ? trim($dados['nome']) : 'Não informado';
$email = isset($dados['email']) ? trim($dados['email']) : 'Não informado';
$telefone = isset($dados['telefone']) ? trim($dados['telefone']) : 'Não informado';
$interesse = isset($dados['interesse']) ? trim($dados['interesse']) : 'Não especificado (Abandono)';

// ==========================================
// CONFIGURAÇÕES DO E-MAIL
// ==========================================
// Aqui é onde os testes e os leads reais chegarão
$para = "brunolima.matematica@gmail.com";

// O Assunto do e-mail
$assunto = "Aviso do Site Zapelli: [$tipo] - $nome";

// Montando o visual do e-mail em HTML para ficar elegante e organizado na sua caixa de entrada
$mensagem = "
<div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;'>
    <div style='background-color: #030303; padding: 20px; text-align: center; border-bottom: 3px solid #D4AF37;'>
        <h2 style='color: #D4AF37; margin: 0;'>Zapelli Seguros & Consórcios</h2>
    </div>
    <div style='padding: 30px; background-color: #fafafa;'>
        <h3 style='margin-top: 0; color: #111;'>Novo Lead Capturado!</h3>
        <p>Um usuário interagiu com o formulário de simulação no site.</p>
        
        <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Evento:</strong></td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; color: #e6683c;'>$tipo</td>
            </tr>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Interesse Principal:</strong></td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #000;'>$interesse</td>
            </tr>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Nome:</strong></td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>$nome</td>
            </tr>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>E-mail:</strong></td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>$email</td>
            </tr>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Telefone / WhatsApp:</strong></td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>$telefone</td>
            </tr>
        </table>
        
        <p style='margin-top: 30px; font-size: 12px; color: #888; text-align: center;'>Este e-mail foi gerado automaticamente pelo site Zapelli.</p>
    </div>
</div>
";

// Headers necessários para o servidor entender que é um HTML e configurar o remetente
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// DICA: Quando o site estiver no ar, esse "From" deve ser contato@dominiodocliente.com.br
$headers .= "From: site@zapelliseguros.com.br" . "\r\n";
$headers .= "Reply-To: $email" . "\r\n";

// Dispara o e-mail usando o servidor da Hostnet
if (mail($para, $assunto, $mensagem, $headers)) {
    echo json_encode(["status" => "sucesso", "mensagem" => "E-mail enviado com sucesso."]);
} else {
    // Isso retornará erro caso o servidor não suporte a função mail (raro na Hostnet)
    echo json_encode(["status" => "erro", "mensagem" => "Falha no envio do e-mail interno."]);
}
?>
