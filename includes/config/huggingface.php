<?php
    define('HF_TOKEN', 'PEGA_TU_API_KEY_AQUI');

    function resumirResenas($reviewsText) {
        // Limpiamos nombres y estrellas para que la IA solo vea los comentarios
        $cleanReviews = preg_replace('/^[A-Za-z\s]+\.?\s*(★|☆)+/m', '', $reviewsText); 
        $cleanReviews = trim($cleanReviews);

        $prompt = "Summarize the following customer reviews in 2-3 sentences. "
        . "Focus on positives, common complaints, and overall sentiment. "
        . "Only return the summary, do not repeat instructions or labels\n\n"
        . $cleanReviews;


        $data = json_encode([
            "inputs" => $prompt
        ]);

        $url = "https://router.huggingface.co/hf-inference/models/philschmid/bart-large-cnn-samsum";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . HF_TOKEN,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        if(curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return "CURL Error: " . $error_msg;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        // Hugging Face devuelve el resumen en ['summary_text'] para este modelo
        $summary = $result[0]['summary_text'] ?? "No se pudo generar resumen.";


        // Elimina cualquier instrucción inicial antes del primer comentario real (asumimos que empieza con una letra mayúscula y no es parte de la instrucción)
        $summary = preg_replace(
            '/^.*?Only return the summary, do not repeat instructions or labels\s*/i',
            '',
            $summary
        );

        return trim($summary);
    }





