## Resource'ı sitemap'e dahil etme:
```php
    public function isIndexable(): true
    {
        return true;
    }
    
    public function modelType(): string
    {
        return 'blog';
    }
```
