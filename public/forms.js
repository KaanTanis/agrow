$(function () {
    $('#contactForm').submit(function (e) {
        e.preventDefault()
        $('#contactFormBtn').attr('disabled', true)
        $('#sending').show();

        axios.post('/send-contact-form', $(this).serialize()).then((res) => {
            $.each(res.data.message, function (index, value) {
                toastr[res.data.status](value)
            })

            if (res.data.status === 'success') {
                $('#contactForm')[0].reset()
            }
            $('#contactFormBtn').attr('disabled', false)
            $('#sending').hide();
        })
    })


    $('#subscribeForm').submit(function (e) {
        e.preventDefault()
        axios.post('/subscribe', $(this).serialize()).then((res) => {
            $.each(res.data.message, function (index, value) {
                toastr[res.data.status](value)
            })
            if (res.data.status === 'success') {
                $('#subscribeForm')[0].reset()
            }
        })
    })
})
