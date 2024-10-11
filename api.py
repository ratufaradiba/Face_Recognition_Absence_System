from deepface import DeepFace
from flask import Flask, jsonify, request
import os

app = Flask(__name__)

@app.route('/absen', methods=['POST'])
def absen():
    try:
        params = request.get_json()
        image_path = "assets/absen/" + params['image1']
        
        # Log untuk memastikan path gambar benar
        if not os.path.exists(image_path):
            return jsonify({'error': f"Image path '{image_path}' does not exist."}), 400
        
        result = DeepFace.find(img_path=image_path, db_path="assets/upload/siswa", enforce_detection=False)
        
        # Log untuk melihat hasil dari DeepFace.find()
        app.logger.info(f"DeepFace.find() result: {result}")
        
        # Mengonversi hasil ke dalam format list of dicts
        result_list_of_dicts = [df.to_dict(orient='records') for df in result]
        
        # Log untuk melihat hasil konversi
        app.logger.info(f"Converted result: {result_list_of_dicts}")
        
        return jsonify(result_list_of_dicts), 200
    except Exception as e:
        app.logger.error(f"Error: {str(e)}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host="localhost", port=4000, debug=True)