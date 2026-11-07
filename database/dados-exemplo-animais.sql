-- database/dados-exemplo-animais.sql
-- Animais fictícios para testes e prints do portfólio.
-- Como usar: phpMyAdmin > banco adocao_animais > aba SQL > colar > Executar.
-- A coluna "foto" fica NULL: depois, edite cada animal em admin/animais/ e envie a foto.

USE adocao_animais;
SET NAMES utf8mb4;

INSERT INTO animais (nome, especie, raca, idade, porte, sexo, descricao, foto, status) VALUES
('Thor', 'cachorro', 'Vira-lata caramelo', '2 anos', 'medio', 'macho',
'Thor é brincalhão e adora passear. Convive bem com crianças e com outros cachorros. Já é vacinado e castrado.',
NULL, 'disponivel'),

('Luna', 'gato', 'Siamês', '1 ano', 'pequeno', 'femea',
'Luna é carinhosa e curiosa. Gosta de colo e de brincar com bolinhas. Vacinada e castrada.',
NULL, 'disponivel'),

('Bolinha', 'cachorro', 'Poodle', '5 anos', 'pequeno', 'macho',
'Bolinha é tranquilo e companheiro, ideal para apartamento. Precisa de banho e tosa regulares.',
NULL, 'disponivel'),

('Mel', 'cachorro', 'Labrador', '3 anos', 'grande', 'femea',
'Mel é cheia de energia e muito dócil. Precisa de espaço e passeios diários. Ótima com crianças.',
NULL, 'disponivel'),

('Mingau', 'gato', 'Vira-lata branco', '6 meses', 'pequeno', 'macho',
'Mingau é um filhote brincalhão que já usa a caixa de areia. Está vermifugado e aguarda a castração.',
NULL, 'disponivel'),

('Nina', 'gato', 'Persa', '4 anos', 'pequeno', 'femea',
'Nina é calma e independente. Gosta de ambientes silenciosos e precisa de escovação frequente.',
NULL, 'disponivel'),

('Rex', 'cachorro', 'Pastor Alemão', '4 anos', 'grande', 'macho',
'Rex é leal e inteligente, aprende comandos com facilidade. Indicado para quem tem experiência com cães grandes.',
NULL, 'em_processo'),

('Pipoca', 'cachorro', 'Vira-lata', '8 meses', 'pequeno', 'femea',
'Pipoca é uma filhote agitada e muito esperta. Está em fase de adaptação e já conhece alguns comandos.',
NULL, 'disponivel'),

('Toby', 'outro', 'Coelho', '1 ano', 'pequeno', 'macho',
'Toby é um coelho dócil que gosta de cenoura e de espaço para pular. Precisa de gaiola ampla e feno fresco.',
NULL, 'disponivel'),

('Estrela', 'gato', 'Vira-lata rajada', '3 anos', 'pequeno', 'femea',
'Estrela foi adotada recentemente por uma família de Joinville. Deixamos aqui como exemplo de animal já adotado.',
NULL, 'adotado');