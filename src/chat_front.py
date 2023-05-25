import gradio as gr
import requests
import EXO

username='admin'
password='admin'
with gr.Blocks(title='Education assistante') as demo:
    with gr.Row():
        exo = gr.Markdown(EXO.exo)
        with gr.Column():
            chatbot = gr.Chatbot()
            msg = gr.Textbox()
            with gr.Row():
                clear = gr.Button("Clear")
                regenerate=gr.Button("Regenerate")

    def respond(message, chat_history):
        
        response = requests.post('http://localhost/ChatAssistancePHP/src/chat_backend.php', data={'user_input': message})        
        
        bot_response = response.text.strip() # Extract the response text
        chat_history.append((message, bot_response)) #add discution to historie queus
        return "", chat_history

    msg.submit(respond, [msg, chatbot], [msg, chatbot])
    clear.click(lambda: None, None, chatbot, queue=False)
    
if __name__ == "__main__":
    demo.launch(share=True , server_port=7861,auth=(username,password))
