<?php

namespace MyApp;

class Todo
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
    Token::create();
  }

  public function processPost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $action = filter_input(INPUT_GET, 'action');
      if ($action) {
        Token::todo_validate();
      }
      switch ($action) {
        case 'add':
          $id = $this->add();
          header('Content-Type: application/json');
          echo json_encode(['id' => $id]);
          break;
        case 'toggle':
          $isDone = $this->toggle();
          header('Content-Type: application/json');
          echo json_encode(['is_done' => $isDone]);
          break;
        case 'delete':
          $this->delete();
          break;
        case 'purge':
          // $this->purge();
          break;
        // case 'contact':
          // $this->purge();
          // echo 'トークンクラスとは関係ないよ';
          // break;
        default:
          return;
      }

      exit;
    }
  }

  private function add()
  {
    $title = trim(filter_input(INPUT_POST, 'title'));
    if ($title === '') {
      return;
    }
  
    $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (:title)");
    $stmt->bindValue('title', $title, \PDO::PARAM_STR);
    $stmt->execute();
    return (int) $this->pdo->lastInsertId();
  }
  
  private function toggle()
  {
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)) {
      return;
    }

    $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = :id");
    $stmt->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    $todo = $stmt->fetch();
    if (empty($todo)) {
      header('HTTP', true, 404);
      exit;
    }
  
    $stmt = $this->pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = :id");
    $stmt->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt->execute();

    return (boolean) !$todo->is_done;
  }
  
  private function delete()
  {
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)) {
      return;
    }
  
    $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = :id");
    $stmt->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt->execute();
  }

  private function purge()
  {
    $this->pdo->query("DELETE FROM todos WHERE is_done = 1");
  }

  public function getAll()
  {
    $stmt = $this->pdo->query("SELECT * FROM todos ORDER BY id DESC");
    $todos = $stmt->fetchAll();
    return $todos;
  }
}