package main

import (
	"encoding/json"
	"log"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/session"
)

func main() {
	app := fiber.New(fiber.Config{
		DisableStartupMessage: true,
		JSONEncoder: func(v interface{}) ([]byte, error) {
			raw, err := json.Marshal(v)
			if err != nil {
				return nil, err
			}
			return append(raw, '\n'), nil
		},
	})
	store := session.New()

	app.Get("/login", func(c *fiber.Ctx) error {
		sess, err := store.Get(c)
		if err != nil {
			return c.Status(500).SendString(err.Error())
		}

		sess.Set("authenticated", true)
		sess.Set("user", "admin")

		sessionID := sess.ID()

		if err := sess.Save(); err != nil {
			return c.Status(500).SendString(err.Error())
		}

		return c.JSON(fiber.Map{
			"message":    "로그인 성공",
			"session_id": sessionID,
		})

	})

	app.Get("/profile", func(c *fiber.Ctx) error {
		sess, err := store.Get(c)
		if err != nil {
			return c.Status(500).SendString(err.Error())
		}

		if sess.Fresh() {
			return c.Status(401).JSON(fiber.Map{
				"error":      "인증되지 않은 접근",
				"session_id": sess.ID(),
				"fresh":      sess.Fresh(),
			})
		}

		user := sess.Get("user")
		auth := sess.Get("authenticated")

		return c.JSON(fiber.Map{
			"message":       "프로필 접근 성공",
			"session_id":    sess.ID(),
			"fresh":         sess.Fresh(),
			"user":          user,
			"authenticated": auth,
		})
	})

	app.Get("/admin", func(c *fiber.Ctx) error {
		sess, err := store.Get(c)
		if err != nil {
			return c.Status(500).SendString(err.Error())
		}

		if sess.Fresh() {
			return c.Status(401).JSON(fiber.Map{
				"error":   "Unauthorized",
				"message": "세션이 없습니다.",
				"fresh":   true,
			})
		}

		return c.JSON(fiber.Map{
			"message":    "관리자 페이지 접근 성공",
			"session_id": sess.ID(),
			"fresh":      sess.Fresh(),
			"user":       sess.Get("user"),
		})
	})
	log.Println("Server running at http://localhost:3000")
	log.Fatal(app.Listen(":3000"))
}
