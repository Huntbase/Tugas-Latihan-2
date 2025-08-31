<style>
    .footer {
        background: var(--sidebar-color);
        color: var(--text-color);
        font-size: 0.9rem;
        transition: var(--trans-03);
    }

    .footer a {
        color: var(--text-color);
        text-decoration: none;
        margin: 0 4px;
    }

    .footer a:hover {
        color: var(--primary-color);
    }
</style>
<footer class="footer py-3 border-top mt-auto">
    <div class="container text-center">
        <span>
            © {{ date('Y') }} Warehouse Dashboard ·
            <a href="#">Privacy</a> ·
            <a href="#">Terms</a>
        </span>
    </div>
</footer>