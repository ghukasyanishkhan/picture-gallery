const itemsPerPage = 10;
let currentPage = 1;
let photosData = [];
const mainElement = document.querySelector('main');

function displayPhotos(photosJson, page = 1) {
    const container = document.getElementById('photos-container');
    container.innerHTML = '';

    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const photosToDisplay = photosJson.slice(startIndex, endIndex);

    photosToDisplay.forEach(photo => {
        const div = document.createElement('div');

        if (!(photo.hasOwnProperty('addCheckbox') && photo.addCheckbox)) {
            const label = document.createElement('label');
            const checkbox = document.createElement('input');
            label.type = 'label'
            label.textContent = 'add wishlist'
            checkbox.type = 'checkbox';
            checkbox.id = 'wishlist-checkbox';
            checkbox.value = photo.id;

            let wishlist = JSON.parse(sessionStorage.getItem('wishlist')) || [];
            checkbox.checked = wishlist.includes(photo.id);

            checkbox.addEventListener('change', (event) => {
                if (event.target.checked) {
                    addToWishlist(photo.id);
                } else {
                    removeFromWishlist(photo.id);
                }
            });
            label.appendChild(checkbox);
            div.appendChild(label);
        }

        const a = document.createElement('a');
        a.href = `/photo-details?id=${photo.id}`;
        const img = document.createElement('img');
        img.src = photo.path;
        img.alt = 'Photo';
        a.appendChild(img);
        div.appendChild(a);

        const p2 = document.createElement('p');
        p2.textContent = photo.name;
        div.appendChild(p2);

        const p1 = document.createElement('p');
        p1.textContent = 'by: ' + photo.user.firstname;
        div.appendChild(p1);

        if (photo.hasOwnProperty('addCheckbox') && photo.addCheckbox) {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = photo.id;
            div.appendChild(checkbox);
        }

        container.appendChild(div);
    });

    if (photosToDisplay.some(photo => photo.hasOwnProperty('addCheckbox') && photo.addCheckbox)) {
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.className = 'delete-photo';
        deleteButton.addEventListener('click', () => {
            const checkedPhotos = Array.from(container.querySelectorAll('input[type="checkbox"]:checked'));
            const photoIdsToDelete = checkedPhotos.map(checkbox => checkbox.value);

            if (photoIdsToDelete.length > 0) {
                deletePhotos(photoIdsToDelete);
            } else {
                alert('Please select photos to delete.');
            }
        });
        mainElement.appendChild(deleteButton);
    }

    updatePagination(photosJson.length, page);
}

function updatePagination(totalItems, currentPage) {
    const paginationContainer = document.getElementById('pagination-container');
    paginationContainer.innerHTML = '';

    const totalPages = Math.ceil(totalItems / itemsPerPage);

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.addEventListener('click', () => {
            displayPhotos(photosData, i);
        });
        if (i === currentPage) {
            button.style.fontWeight = 'bold';
        }
        paginationContainer.appendChild(button);
    }
}

function pagination(photosJson) {
    photosData = photosJson;
    displayPhotos(photosData, currentPage);
}

function deletePhotos(photoIds) {
    fetch('/photos/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ids: photoIds}),
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        })
        .then(data => {
            console.log('Deleted:', data);
            location.reload();
        })
        .catch(error => {
            console.error('Error deleting photos:', error);
        });
}

function addToWishlist(photoId) {
    let wishlist = JSON.parse(sessionStorage.getItem('wishlist')) || [];

    if (!wishlist.includes(photoId)) {
        wishlist.push(photoId);
        sessionStorage.setItem('wishlist', JSON.stringify(wishlist));
        alert(`Photo ${photoId} added to wishlist.`)
    }
}

function removeFromWishlist(photoId) {
    let wishlist = JSON.parse(sessionStorage.getItem('wishlist')) || [];

    const index = wishlist.indexOf(photoId);
    if (index !== -1) {
        wishlist.splice(index, 1);
        sessionStorage.setItem('wishlist', JSON.stringify(wishlist));
        alert(`Photo ${photoId} removed from wishlist.`);
    }

}

function displayWishlist(photos) {
    const wishlistContainer = document.getElementById('photos-wishlist');
    wishlistContainer.innerHTML = '';

    let wishlist = JSON.parse(sessionStorage.getItem('wishlist')) || [];

    const wishlistPhotos = photos.filter(photo => wishlist.includes(photo.id));

    wishlistPhotos.forEach(photo => {
        const div = document.createElement('div');

        const a = document.createElement('a');
        a.href = `/photo-details?id=${photo.id}`; // Send photo ID to the controller
        const img = document.createElement('img');
        img.src = photo.path;
        img.alt = 'Photo';
        a.appendChild(img);
        div.appendChild(a);

        const p2 = document.createElement('p');
        p2.textContent = photo.name;
        div.appendChild(p2);

        const p1 = document.createElement('p');
        p1.textContent = 'by: ' + photo.user.firstname;
        div.appendChild(p1);

        wishlistContainer.appendChild(div);
    });

    if (wishlistPhotos.length === 0) {
        wishlistContainer.innerHTML = '<p>No photos in the wishlist.</p>';
    }
}
