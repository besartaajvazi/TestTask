import React, { useState } from 'react';
import axios from 'axios';

const UploadComponent = () => {
  const [selectedFile, setSelectedFile] = useState(null);

  const handleFileChange = (event) => {
    setSelectedFile(event.target.files[0]);
  };

  const handleFileUpload = () => {
    if (selectedFile) {
      const formData = new FormData();
      formData.append('import_file', selectedFile);
  
      axios.post('/import', formData)
        .then((response) => {
          console.log(response.data);
        })
        .catch((error) => {
          console.error(error.response ? error.response.data : error.message);
        });
    } else {
      console.error('No file selected.');
    }
  };
  
  return (
    <div>
      <input type="file" onChange={handleFileChange} />
      <button onClick={handleFileUpload}>Upload</button>
    </div>
  );
};

export default UploadComponent;
