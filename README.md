# API-StackOverflow

This project provides an API interface to interact with StackOverflow data. You can search for questions, retrieve answers, and explore StackOverflow documentation.

## Commands

### Run Project

To start the project, run:

```bash
make init
```

### Run Tests
To execute tests, use:

```bash
make run-test
```


### Endpoints
API Documentation: http://localhost:8080/api/doc

Search Questions: http://localhost:8080/api/questions?order=desc&sort=activity&site=stackoverflow&title=php

Get Answers: http://localhost:8080/api/answers?order=desc&sort=activity&site=stackoverflow&filter=withBody

```plaintext
src
└── App
├── Answer
│   ├── Application
│   │   ├── AnswerResponse.php
│   │   └── GetAnswersService.php
│   ├── Domain
│   │   └── Answer.php
│   └── Infrastructure
│       └── Client
├── Kernel.php
├── Question
│   ├── Application
│   │   ├── QuestionResponse.php
│   │   └── SearchQuestionsService.php
│   ├── Domain
│   │   └── Question.php
│   └── Infrastructure
│       └── Client
└── Shared
├── Domain
│   └── Client
└── Infrastructure
└── Client
````