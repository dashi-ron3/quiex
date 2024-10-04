let currentQuestion = 1;
    const totalQuestions = 10;
    let timer;
    let timeLeft = 10;

    function updateQuestionNumber() {
        document.getElementById('questionNumber').innerText = `${currentQuestion}<br>
                                                                ー <br>
                                                                10`;
    }

    function startTimer() {
        clearInterval(timer);
        timeLeft = 10;
        document.querySelector('.timer').innerText = `00:${timeLeft < 10 ? '0' : ''}${timeLeft}`;

        timer = setInterval(() => {
            timeLeft--;
            document.querySelector('.timer').innerText = `00:${timeLeft < 10 ? '0' : ''}${timeLeft}`;

            if (timeLeft <= 0) {
                clearInterval(timer);
                nextQuestion();
            }
        }, 1000);
    }

    // NEXT QUESTION QUERY
    //function nextQuestion() {
        //if (currentQuestion < totalQuestions) {
            //currentQuestion++;
            //updateQuestionNumber();
            //startTimer();
            // NEXT QUESTION LOADER
            //document.querySelector('.container p').innerText = `New question content for Question ${currentQuestion} goes here...`;
        //} else {
            //clearInterval(timer);
            //alert('Complete!');
        //}
    //}

    function startQuiz() {
        document.querySelector('.start-screen').style.display = 'none';
        document.querySelector('.quiz-screen').style.display = 'flex';
        updateQuestionNumber();
        startTimer();
    }

    function handleAnswerClick() {
        nextQuestion();
    }

    document.querySelectorAll('.choice').forEach(choice => {
        choice.addEventListener('click', handleAnswerClick);
    });