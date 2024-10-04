document.addEventListener('DOMContentLoaded', function() {
    let questions = [
        { 
            id: 1, 
            question: "What is the capital of the Philippines?", 
            choices: ["Manila", "Quezon City", "Cebu", "Davao"]
        },
        { 
            id: 2, 
            question: "What is 2 + 2?", 
            choices: ["3", "4", "5", "6"]
        },
        { 
            id: 3, 
            question: "Who wrote 'Pride and Prejudice'?", 
            choices: ["William Shakespeare", "Jane Austen", "J.K. Rowling", "Emily Brontë"]
        },
        { 
            id: 4, 
            question: "What is the powerhouse of the cell called?", 
            choices: ["Nucleus", "Mitochondria", "Ribosome", "Chloroplast"]
        },
        { 
            id: 5, 
            question: "Who was the first president of the United States?", 
            choices: ["Abraham Lincoln", "George Washington", "Thomas Jefferson", "John Adams"]
        },
        { 
            id: 6, 
            question: "How many continents are there on Earth?", 
            choices: ["5", "6", "7", "8"]
        },
        { 
            id: 7, 
            question: "What is the largest planet in our solar system?", 
            choices: ["Earth", "Mars", "Jupiter", "Saturn"]
        },
        { 
            id: 8, 
            question: "What is the speed of light?", 
            choices: ["300,000 km/s", "150,000 km/s", "1,000 km/s", "1,500 km/s"]
        },
        { 
            id: 9, 
            question: "How do you say 'Hello' in French?", 
            choices: ["Bonjour", "Hola", "Ciao", "Hallo"]
        },
        { 
            id: 10, 
            question: "In what animal family does a capybara belong to?", 
            choices: ["Rodent", "Reptile", "Bird", "Canidae"]
        },
        { 
            id: 11, 
            question: "Who invented the telephone?", 
            choices: ["Alexander Graham Bell", "Nikola Tesla", "Thomas Edison", "Taylor Swift"]
        },
        { 
            id: 12, 
            question: "What is the chemical symbol for gold?", 
            choices: ["Au", "Ag", "Gd", "Fe"]
        }
    ];

    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    function displayQuestions(questionsArray) {
        const quizContainer = document.getElementById("quizContainer");
        quizContainer.innerHTML = '';

        questionsArray.forEach(question => {
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question');

            const questionText = document.createElement('p');
            questionText.innerText = question.question;
            questionDiv.appendChild(questionText);

            let shuffledChoices = shuffle([...question.choices]);

            const choicesList = document.createElement('ul');
            shuffledChoices.forEach(choice => {
                const choiceItem = document.createElement('li');
                choiceItem.innerText = choice;
                choicesList.appendChild(choiceItem);
            });

            questionDiv.appendChild(choicesList);
            quizContainer.appendChild(questionDiv);
        });
    }

    document.getElementById("randomizeButton").addEventListener("click", function() {
        let randomizedQuestions = shuffle(questions);
        displayQuestions(randomizedQuestions);
    });
});
