import React, { useState, useEffect } from 'react';
import { Trash2, Edit } from 'lucide-react'; // Importamos iconos para la UI

// El componente principal de la aplicación React
const App = () => {
  // Estados para gestionar los datos de la aplicación
  const [drafts, setDrafts] = useState([]); // Almacena la lista de borradores
  const [title, setTitle] = useState(''); // Almacena el título del nuevo borrador
  const [content, setContent] = useState(''); // Almacena el contenido del nuevo borrador
  const [loading, setLoading] = useState(false); // Indicador de carga para las peticiones
  const [error, setError] = useState(null); // Manejo de errores de las peticiones

  // URL base de tu backend PHP. Asegúrate de que esta URL sea la correcta.
  // Por ahora, usamos una URL local.
  const API_BASE_URL = 'https://gestion-de-contenidos.wuaze.com'; 

  // useEffect se ejecuta una vez al cargar el componente para leer los borradores
  useEffect(() => {
    fetchDrafts();
  }, []);

  // Función para obtener los borradores desde la API de PHP
  const fetchDrafts = async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch(`${API_BASE_URL}/api/borradores/leer.php`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      setDrafts(data);
    } catch (err) {
      setError('No se pudieron cargar los borradores.');
      console.error('Error al cargar borradores:', err);
    } finally {
      setLoading(false);
    }
  };

  // Función para manejar la creación de un nuevo borrador
  const handleCreateDraft = async (e) => {
    e.preventDefault();
    if (!title || !content) {
      setError('El título y el contenido no pueden estar vacíos.');
      return;
    }

    setLoading(true);
    setError(null);
    try {
      const response = await fetch(`${API_BASE_URL}/api/borradores/crear_test.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ titulo: title, contenido: content }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      // Volvemos a cargar los borradores para ver el nuevo
      await fetchDrafts();
      // Limpiamos los campos del formulario
      setTitle('');
      setContent('');
    } catch (err) {
      setError('Error al crear el borrador.');
      console.error('Error al crear borrador:', err);
    } finally {
      setLoading(false);
    }
  };

  // Función para manejar la eliminación de un borrador
  const handleDeleteDraft = async (id) => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch(`${API_BASE_URL}/api/borradores/eliminar.php`, {
        method: 'DELETE', // Usamos el método DELETE, ya que tu script de PHP lo maneja
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      // Volvemos a cargar los borradores
      await fetchDrafts();
    } catch (err) {
      setError('Error al eliminar el borrador.');
      console.error('Error al eliminar borrador:', err);
    } finally {
      setLoading(false);
    }
  };
  
  // Renderizado de la interfaz de usuario
  return (
    <div className="bg-gray-50 min-h-screen flex items-center justify-center p-4 sm:p-6 font-sans">
      <div className="max-w-4xl w-full bg-white rounded-xl shadow-lg p-6 sm:p-10 border border-gray-200">
        <header className="text-center mb-10">
          <h1 className="text-3xl sm:text-4xl font-bold text-gray-800">
            Generador de Contenido Estratégico
          </h1>
          <p className="mt-2 text-md text-gray-500">
            Crea y gestiona tus borradores de contenido fácilmente.
          </p>
        </header>

        {/* Sección de Notificaciones y Errores */}
        {loading && (
          <div className="bg-indigo-100 border-l-4 border-indigo-500 text-indigo-700 p-4 mb-4 rounded-md" role="alert">
            <p>Cargando...</p>
          </div>
        )}
        {error && (
          <div className="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
            <p>{error}</p>
          </div>
        )}

        {/* Formulario para crear un nuevo borrador */}
        <form onSubmit={handleCreateDraft} className="mb-10 p-6 bg-gray-50 rounded-lg shadow-inner border border-gray-100">
          <h2 className="text-2xl font-bold text-gray-700 mb-5">Crear Nuevo Borrador</h2>
          <div className="mb-4">
            <label htmlFor="title" className="block text-gray-700 text-sm font-semibold mb-2">
              Título
            </label>
            <input
              type="text"
              id="title"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200"
              placeholder="Ej. Estrategia de Marketing Digital"
            />
          </div>
          <div className="mb-4">
            <label htmlFor="content" className="block text-gray-700 text-sm font-semibold mb-2">
              Contenido
            </label>
            <textarea
              id="content"
              value={content}
              onChange={(e) => setContent(e.target.value)}
              className="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200"
              rows="6"
              placeholder="Escribe aquí el contenido de tu borrador..."
            ></textarea>
          </div>
          <button
            type="submit"
            disabled={loading}
            className="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 disabled:bg-gray-400"
          >
            {loading ? 'Guardando...' : 'Guardar Borrador'}
          </button>
        </form>

        {/* Lista de borradores guardados */}
        <div className="p-6 bg-gray-50 rounded-lg shadow-inner border border-gray-100">
          <h2 className="text-2xl font-bold text-gray-700 mb-5">Borradores Guardados</h2>
          {drafts.length === 0 && !loading && (
            <p className="text-gray-500 text-center">No hay borradores guardados.</p>
          )}
          <ul className="space-y-4">
            {drafts.map((draft) => (
              <li key={draft.id} className="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div className="flex-1 min-w-0 pr-4">
                  <h3 className="text-lg font-semibold text-gray-800 break-words">{draft.titulo}</h3>
                  <p className="text-sm text-gray-500 mt-1 break-words">{draft.contenido.substring(0, 100)}...</p>
                </div>
                <div className="mt-4 sm:mt-0 flex space-x-2">
                  <button
                    onClick={() => handleDeleteDraft(draft.id)}
                    className="p-2 rounded-full text-red-500 bg-red-100 hover:bg-red-200 transition duration-200"
                    aria-label={`Eliminar borrador ${draft.titulo}`}
                  >
                    <Trash2 size={20} />
                  </button>
                  {/* El botón de editar aún no tiene funcionalidad, pero es un placeholder */}
                  <button
                    className="p-2 rounded-full text-blue-500 bg-blue-100 hover:bg-blue-200 transition duration-200"
                    aria-label={`Editar borrador ${draft.titulo}`}
                  >
                    <Edit size={20} />
                  </button>
                </div>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
};

export default App;
