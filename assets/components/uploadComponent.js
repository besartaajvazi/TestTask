import React, { useState } from 'react';
import axios from 'axios';

const UploadComponent = () => {
  const [selectedFile, setSelectedFile] = useState(null);
  const [flash, setFlash] = useState({ message: null, type: null });

  const handleFileChange = (event) => {
    setSelectedFile(event.target.files[0]);
  };
  
  const handleFileUpload = () => {
    if (selectedFile) {
      const formData = new FormData();
      formData.append('import_file', selectedFile);

      axios.post('/import', formData)
        .then((response) => { 
          window.location.reload(); 
          console.log(response.data);   
          setFlash({ message: 'File uploaded successfully!', type: 'success' });              
        })
        .catch((error) => {
          console.error(error.response ? error.response.data : error.message);
          setFlash({ message: 'Invalid file format.', type: 'error' });
        });
    } else {
      console.error('No file selected.');
      setFlash({ message: 'No file selected.', type: 'error' });
    }
  };

  return (
    <div>
      {flash.message && (
        <div className={`alert ${flash.type}`}>
          <span>{flash.message}</span>
        </div>
      )}
       <input type="file" onChange={handleFileChange} />
      <button onClick={handleFileUpload}>Upload</button>

      <style>
        {`
        .alert {
          padding: 10px;
          margin-bottom: 10px;
        }

        .success {
          background-color: #d4edda;
          color: #155724;
        }

        .error {
          background-color: #f8d7da;
          color: #721c24;
        }

        .alert span {
          margin-right: 10px;
        }
      `}
      </style>
    </div>
  );
};

export default UploadComponent;
