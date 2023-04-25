// single url
async function handleRequest(request) {
  const url = 'http://xyz.com/filter.php'; // Replace with the single URL

  const response = await fetch(url);

  if (response.ok) {
    console.log(`Successfully fetched data`);
  } else {
    console.error(`Failed to fetch data, status ${response.status}`);
  }

  return response;
}

addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request));
});


// two url
async function handleRequest(request) {
  const url1 = 'http://xyz.com/download.php';
  const url2 = 'http://xyz.com/filter.php';

  const [response1, response2] = await Promise.all([fetch(url1), fetch(url2)]);

  if (response1.ok) {
    console.log(`Successfully fetched download`);
  } else {
    console.error(`Failed to fetch download, status ${response1.status}`);
  }

  if (response2.ok) {
    console.log(`Successfully fetched filter`);
  } else {
    console.error(`Failed to fetch filter, status ${response2.status}`);
  }

  return response2;
}

addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request));
});
