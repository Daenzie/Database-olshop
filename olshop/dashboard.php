/* === Pagination === */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 2rem;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.pagination a {
    padding: 10px 16px;
    text-decoration: none;
    border-radius: 8px;
    background-color: #f3e8ff;
    color: #770faf;
    font-family: 'Baloo Thambi', cursive;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.pagination a:hover {
    background-color: #770faf;
    color: #fff;
    transform: translateY(-2px);
}

.pagination .active {
    background-color: #770faf;
    color: #fff;
    pointer-events: none;
}

/* Biar gambar produk lebih konsisten */
.product-card img {
    width: 100%;
    height: 220px;
    max-height: 220px;  /* batas tinggi */
    object-fit: cover;  /* biar nggak ketarik */
    border-radius: 15px;
    margin-bottom: 1rem;
    background-color: #f3e8ff;
}
