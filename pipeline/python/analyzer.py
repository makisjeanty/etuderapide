import sys
import json

def main():
    # Lê todos os dados da entrada padrão (stdin) forçando UTF-8 (evita quebrar acentos no Windows)
    input_data = sys.stdin.buffer.read().decode('utf-8')
    
    if not input_data:
        print(json.dumps({"error": "No input data provided"}))
        return

    try:
        data = json.loads(input_data)
        
        # ==========================================
        # --- LÓGICA DE IA (PyTorch, LangChain) ---
        # ==========================================
        
        text_to_analyze = data.get("text", "")
        command = data.get("command", "analyze")
        
        # Mocks temporários até plugar a OpenAI
        analysis_result = ""
        if command == "seo_title":
            analysis_result = f"{text_to_analyze} | Otimizado para SEO"
        elif command == "seo_description":
            analysis_result = f"Descubra todos os detalhes sobre '{text_to_analyze}'. Uma abordagem completa focada em resultados e inovação."
        elif command == "summary":
            analysis_result = f"Este é um resumo gerado automaticamente pela IA para o projeto '{text_to_analyze}', focado em capturar a atenção imediata do leitor."
        elif command == "description":
            analysis_result = f"O projeto '{text_to_analyze}' foi desenvolvido com foco em alta performance e escalabilidade.\n\nPrincipais desafios superados:\n- Arquitetura robusta\n- Integração perfeita\n- Experiência do usuário aprimorada."
        elif command == "cta":
            analysis_result = f"Quero acelerar {text_to_analyze} com a Makis Digital"
        elif command == "business_audit":
            analysis_result = {
                "score": 85,
                "opportunities": [
                    "Implementação de Chatbot inteligente para redução de 40% no suporte.",
                    "Otimização de conversão (CRO) na página principal para dobrar leads.",
                    "Automação de e-mails transacionais para retenção de clientes."
                ],
                "verdict": f"O negócio '{text_to_analyze}' tem um alto potencial de escala digital, mas está perdendo eficiência em processos manuais que poderiam ser automatizados com IA."
            }
        else:
            analysis_result = "Comando desconhecido ou análise padrão concluída."

        result = {
            "status": "success",
            "received": text_to_analyze,
            "analysis": analysis_result,
            "metrics": {
                "length": len(text_to_analyze),
                "words": len(text_to_analyze.split())
            }
        }
        
        # A resposta DEVE ser um JSON válido impresso no stdout (usando utf-8 para acentos)
        output_json = json.dumps(result, ensure_ascii=False)
        sys.stdout.buffer.write(output_json.encode('utf-8'))
        
    except json.JSONDecodeError:
        error_json = json.dumps({"error": "Invalid JSON input"})
        sys.stdout.buffer.write(error_json.encode('utf-8'))
    except Exception as e:
        error_json = json.dumps({"error": str(e)})
        sys.stdout.buffer.write(error_json.encode('utf-8'))

if __name__ == "__main__":
    main()
