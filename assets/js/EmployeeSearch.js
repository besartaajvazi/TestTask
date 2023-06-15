import React, { useState, useEffect } from 'react';

const EmployeeSearch = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [results, setResults] = useState([]);

  useEffect(() => {
    // Fetch search results from the Symfony backend
    const fetchData = async () => {
      try {
        const response = await fetch(`/employee/search?q=${searchTerm}`);
        const data = await response.json();
        setResults(data);
      } catch (error) {
        console.error(error);
      }
    };

    fetchData();
  }, [searchTerm]);

  return (
    <div>
      <input
        type="text"
        value={searchTerm}
        onChange={(e) => setSearchTerm(e.target.value)}
      />
      <ul>
        {results.map((employee) => (
          <li key={employee.username}>{employee.name}</li>
        ))}
      </ul>
    </div>
  );
};

export default EmployeeSearch;
