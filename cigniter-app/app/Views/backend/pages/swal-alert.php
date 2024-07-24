<script>
    Swal.fire({
        icon: '<?= esc($alertType) ?>',
        title: '<?= esc($alertType) ?>',
        text: '<?= urldecode($alertMessage) ?>'
    });
</script>
